use tonic::{transport::Server, Request, Response, Status};
use pokerhands::{HandRequest, BestHandResult};
use pokerhands::best_hand_result::{ResultCard, Category};
use pokerhands::best_hand_result::result_card::{Suit, Rank};
use pokerhands::best_hand_server::{BestHand, BestHandServer};
//use pokerhands::hand_request::{HandCard};

pub mod pokerhands {
    tonic::include_proto!("pokerhands");
}

#[derive(Debug, Default)]
pub struct BestHandService{}

#[tonic::async_trait]
impl BestHand for BestHandService {
    async fn hand(&self, request: Request<HandRequest>) -> Result<Response<BestHandResult>, Status> {
        let r = request.into_inner();
        let mut cards: Vec<ResultCard> = Vec::new();
        for request_card in r.cards {
            cards.push(ResultCard {
                rank: request_card.rank,
                suit: request_card.suit
            });
        }

        Ok(Response::new(get_best_hand(&cards)))
    }
}

/**
 * Matches all cases of poker hands
 */
 fn get_best_hand(cards: &Vec<ResultCard>) -> BestHandResult {
    let mut result: Vec<ResultCard>;
    let category: i32;
    result = check_flush(&cards);
    if !result.is_empty() {
        let result2: Vec<ResultCard> = check_straight(&cards);
        if !result2.is_empty() {
            let cat: i32;
            if result2[0].rank == Rank::Ace as i32 {
                cat = Category::RoyalFlush as i32
            } else {
                cat = Category::StraightFlush as i32
            }
            return BestHandResult {
                category: cat,
                cards: result2
            }
        }

        return BestHandResult {
            category: Category::Flush as i32,
            cards: result
        }
    }
    result = check_straight(&cards);
    if !result.is_empty() {
        return BestHandResult {
            category: Category::Straight as i32,
            cards: result
        }
    }

    XOfAKind {cards: result, category: category} = check_x_of_a_kind(&cards);
    if !result.is_empty() {
        return BestHandResult {
            category: category,
            cards: result
        }
    }

    return BestHandResult {
        category: 0,
        cards: Vec::new()
    }
 }

fn check_straight(cards: &Vec<ResultCard>) -> Vec<ResultCard> {
    // Lenght +1 since index starts at 0
    let mut array: [Vec<ResultCard>; (Rank::Ace as usize) + 1] = Default::default();
    let mut result: Vec<ResultCard>;
    for card in cards {
        array[card.rank as usize].push(card.clone());
    }
    for (i, rank) in array.iter().enumerate().rev() {
        result = Vec::new();
        let mut straight: bool = false;
        if i >= 4 && rank.len() > 0 {
            result.push(rank[0].clone());            
            straight = true;
            for j in 1..5 {
                if !straight {
                    break;
                }
                if array[i - j].len() == 0 {
                    straight = false;
                } else {
                    result.push(array[i - j][0].clone());
                }                
            }
        }
        if straight {
            return result;
        }
    }

    return Vec::new();
}

 /**
  * Create Suit Enum instance based on input value, defaults to Suit::Spades
  */
impl Suit {
    fn parse_i32(value: i32) -> Suit {
        match value {
            0 => Suit::Hearts,
            1 => Suit::Diamonds,
            2 => Suit::Clubs,
            3 | _ => Suit::Spades,
        }
    }
}

/**
 * Returns a list of cards if a flush exists
 * Returns an empty list if not
 */
 fn check_flush(cards: &Vec<ResultCard>)-> Vec<ResultCard> {
    let (mut h, mut d, mut c, mut s): (Vec<ResultCard>, Vec<ResultCard>, Vec<ResultCard>, Vec<ResultCard>) = (Vec::new(), Vec::new(), Vec::new(), Vec::new());
    for card in cards {
        match Suit::parse_i32(card.suit) {
            Suit::Hearts    => h.push(card.clone()),
            Suit::Diamonds  => d.push(card.clone()),
            Suit::Clubs     => c.push(card.clone()),
            Suit::Spades    => s.push(card.clone())
        }
    }

    for suit in [h, d, c ,s] {
        if suit.len() >= 5 {
            return suit;
        }
    }

    return Vec::new();
 }

struct XOfAKind {
    cards: Vec<ResultCard>,
    category: i32
}

 fn check_x_of_a_kind(cards: &Vec<ResultCard>) -> XOfAKind {
    let mut array: [Vec<ResultCard>; (Rank::Ace as usize) + 1] = Default::default();
    for card in cards {
        array[card.rank as usize].push(card.clone());
    }
    for (i, rank) in array.iter().enumerate() {
        if rank.len() == 4 {
            return XOfAKind {
                cards: rank.clone(),
                category: Category::FourOfAKind as i32
            }
        }
        if rank.len() == 3 {
            for (j, rank2) in array.iter().enumerate() {
                if rank2.len() == 2 && i != j {
                    let mut result: Vec<ResultCard> = Vec::new();
                    for c in rank {
                        result.push(c.clone());
                    }
                    for d in rank2 {
                        result.push(d.clone());
                    }
                    return XOfAKind {
                        cards: result,
                        category: Category::FullHouse as i32
                    }
                }
            }

            return XOfAKind {
                cards: rank.clone(),
                category: Category::ThreeOfAKind as i32
            }
        }

        if rank.len() == 2 {
            for (j, rank2) in array.iter().enumerate() {
                if rank2.len() == 3 && i != j {
                    let mut result: Vec<ResultCard> = Vec::new();
                    for d in rank2 {
                        result.push(d.clone());
                    }
                    for c in rank {
                        result.push(c.clone());
                    }                    
                    return XOfAKind {
                        cards: result,
                        category: Category::FullHouse as i32
                    }
                }
                if rank2.len() == 2 && i != j {
                    let mut result: Vec<ResultCard> = Vec::new();
                    for c in rank {
                        result.push(c.clone());
                    }
                    for d in rank2 {
                        result.push(d.clone());
                    }
                    return XOfAKind {
                        cards: result,
                        category: Category::TwoPair as i32
                    }
                }
            }

            return XOfAKind {
                cards: rank.clone(),
                category: Category::OnePair as i32
            }
        }
    }



    return XOfAKind { 
        cards: Vec::new(), 
        category: 0
    }
 }

#[tokio::main]
async fn main() -> Result<(), Box<dyn std::error::Error>> {
    let address = "[::1]:50051".parse().unwrap();
    let best_hand_service = BestHandService::default();

    Server::builder()
        .add_service(BestHandServer::new(best_hand_service))
        .serve(address)
        .await?;
    Ok(())
}
